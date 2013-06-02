<?php
    include_once($BASE_PATH . 'common/DatabaseException.php');

    function insertTag($tagname) {
        global $db;
        $errors = new DatabaseException();
        
        try {
            $stmt = $db->prepare("INSERT INTO tag (tagname, creationdate) VALUES (?, now())");
            $stmt->execute(array($tagname));
            return $db->lastInsertId('tag_tagid_seq');
        } catch(Exception $e) {
            $errors->addError('tag', 'error processing insert into tag table');
            throw($errors);
        }
    }

    function addTagToQuestion($questionid, $tagid) {
        global $db;
        try {
            $stmt = $db->prepare("INSERT INTO questiontag (questionid, tagid) VALUES (?, ?)");
            $stmt->execute(array($questionid, $tagid));
        } catch(Exception $e) {
            $errors->addError('questiontag', 'error processing insert into questiontag table');
            throw($errors);
        }
    }

    function getNumberOfTagsWithSorting($sort) {
        global $db;
        $query = "SELECT COUNT(*) AS total FROM tag, questiontag WHERE tag.tagid = questiontag.tagid ";
        $now = date('Y-m-d', time()-1296000); // current date minus 15 days

        switch ($sort) {
            case 'popular':
            case 'name':
                $query = $query."GROUP BY tag.tagid";
                break;
            case 'new':
                $query = $query."AND creationdate > ? GROUP BY tag.tagid";
                break;
            default:
                throw new Exception("getTagsWithSorting: Invalid sorting");
                break;
        }

        $stmt = $db->prepare($query);
        if($sort == 'new')
            $stmt->execute(array($now));
        else
            $stmt->execute();
        return $stmt->fetchAll();
    }

    //SELECT tag.*, COUNT(*) AS used FROM tag LEFT OUTER JOIN questiontag ON (tag.tagid = questiontag.tagid) GROUP BY tag.tagid ORDER BY used DESC, tagname

    function getTagsWithSorting($sort, $limit, $offset) {
        global $db;
        $query = "SELECT tag.*, COUNT(*) AS used FROM tag, questiontag WHERE tag.tagid = questiontag.tagid ";
        $now = date('Y-m-d', time()-1296000); // current date minus 15 days

        switch ($sort) {
            case 'popular':
                $query = $query."GROUP BY tag.tagid ORDER BY used DESC, tagname";
                break;
            case 'name':
                $query = $query."GROUP BY tag.tagid ORDER BY tagname";
                break;
            case 'new':
                $query = $query."AND creationdate > ? GROUP BY tag.tagid ORDER BY creationdate DESC, tagname";
                break;
            default:
                throw new Exception("getTagsWithSorting: Invalid sorting");
                break;
        }

        if($limit !== null && $offset !== null) {
            $query = $query." LIMIT ? OFFSET ?";
        }

        $stmt = $db->prepare($query);

        if($limit !== null && $offset !== null) {
            if($sort == 'new')
                $stmt->execute(array($now, $limit, $offset));
            else
                $stmt->execute(array($limit, $offset));
        } else {
            if($sort == 'new')
                $stmt->execute(array($now));
            else
                $stmt->execute();
        }
        return $stmt->fetchAll();
    }

    function removeTagFromQuestion($questionid, $tagid) {
        global $db;
        try {
            $stmt = $db->prepare("DELETE FROM questiontag WHERE questionid = ? AND tagid = ?");
            $stmt->execute(array($questionid, $tagid));
        } catch(Exception $e) {
            $errors->addError('questiontag', 'error processing delete from questiontag table');
            throw($errors);
        }
    }

    function getQuestionsWithTag($tagid) {
        global $db;
        try {
            $stmt = $db->prepare("SELECT questionid FROM questiontag WHERE tagid = ?");
            $stmt->execute(array($tagid));
            return $stmt->fetchAll();
        } catch(Exception $e) {
            $errors->addError('questiontag', 'error processing insert into questiontag table');
            throw($errors);
        }
    }

    function getTagByName($tagname) {
        global $db;
        $result = $db->prepare("SELECT * FROM tag WHERE tagname = ?");
        $result->execute(array($tagname));
        return $result->fetch();
    }

    function getTagsOfQuestion($questionid) {
        global $db;
        $result = $db->prepare("SELECT tag.* FROM tag, questiontag WHERE tag.tagid = questiontag.tagid AND questionid = ?");
        $result->execute(array($questionid));
        return $result->fetchAll();
    }
?>
